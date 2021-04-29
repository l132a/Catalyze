import styled from "styled-components";
import { imgHeadLeft } from "../../assets";

const HeadLine = () => {
  return (
    <Container>
      <HeadLeft>
        <HeadImg src={imgHeadLeft} />
        <ContainerTitleLeft>
          <TitleLeft>
            Lorem ipsum is placeholder text commonly used in the graphic
          </TitleLeft>
          <ContentLeft>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore
          </ContentLeft>
        </ContainerTitleLeft>
      </HeadLeft>
      <HeadRight>
        <ContainerTitleRight>
          <h5>1</h5>
        </ContainerTitleRight>
        <ContainerTitleRight>
          <h5>2</h5>
        </ContainerTitleRight>
      </HeadRight>
    </Container>
  );
};

export default HeadLine;

const Container = styled.div`
  display: flex;
  background-color: black;
  height: 400px;
`;

const HeadLeft = styled.div`
  flex: 0.6;
  position: relative;
  border-right: #c2c2c2 1px solid;
`;

const HeadImg = styled.img`
  height: 400px;
  width: 100%;
`;

const HeadRight = styled.div`
  flex: 0.4;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
`;

const ContainerTitleLeft = styled.div`
  color: white;
  margin: 0 30px;
  position: absolute;
  bottom: 30px;
`;

const TitleLeft = styled.a`
  font-weight: bold;
  font-size: 24pt;
  font-family: "Montserrat";
`;

const ContentLeft = styled.div`
  margin-top: 20px;
  font-size: 15pt;
  font-family: "OpenSans";
`;

const ContainerTitleRight = styled.div`
  background-color: blue;
  font-weight: bold;
  font-size: 28pt;
  font-family: "Montserrat";
  color: white;
  justify-content: center;
  flex: 1;
  display: flex;
`;
